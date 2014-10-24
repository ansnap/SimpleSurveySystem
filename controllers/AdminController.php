<?php

/**
 * To create surveys
 */
class AdminController extends BaseController
{

    public function index()
    {
        $db = $this->registry->get('db');

        $qry = $db->query('SELECT * FROM poll');

        $polls = [];
        while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
            switch ($row['status']) {
                case 'active':
                    $polls['active'][] = $row;
                    break;
                case 'draft':
                    $polls['draft'][] = $row;
                    break;
                case 'archived':
                    $polls['archived'][] = $row;
                    break;
            }
        }
        uksort($polls, function($a, $b) {
            return $a == 'active' ? -1 : ($a == 'draft' && $b == 'archived' ? -1 : 1);
        });


        $this->view([
            'polls' => $polls,
            'template' => 'admin/index',
        ]);
    }

    public function create($params)
    {
        $args = [
            'template' => 'admin/create',
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $poll = filter_input_array(INPUT_POST);
            unset($poll['questions']['{num}']);

            if (empty($poll['title'])) {
                $args['errors']['title'] = 'Не заполнено название опроса';
            }

            if (!$poll['questions']) {
                $args['errors']['question'] = 'Должен быть введен хотя бы 1 вопрос';
            }

            $poll['questions'] = array_filter($poll['questions'], function($var) {
                return $var['title'] ? $var : false;
            });
            foreach ($poll['questions'] as $k => $q) {
                if (!isset($q['is_multiple'])) {
                    $args['errors']['type'] = 'Должен быть выбран тип всех вопросов';
                }

                $poll['questions'][$k]['answers'] = array_filter($q['answers'], function($var) {
                    return $var['title'] ? $var : false;
                });
                if (count($q['answers']) < 2) {
                    $args['errors']['answers'] = 'Для каждого введенного вопроса должно быть введено не менее 2х ответов';
                }

                if (isset($q['is_required'])) {
                    $has_required = true;
                }
            }

            if (!isset($has_required)) {
                $args['errors']['required'] = 'Должен быть хотя бы 1 обязательный вопрос';
            }

            if (!isset($args['errors'])) {
                $poll = $this->savePoll($poll);

                header('Location: ' . HOME_URL . '/admin');
                die;
            }
        } else if (isset($params[0]) && is_numeric($params[0])) {
            $db = $this->registry->get('db');
            $poll = PollRepository::getPoll($params[0], $db);
            if (!$poll) {
                die('Wrong poll ID');
            }
        }

        if (isset($poll)) {
            $args['poll'] = $poll;
        }

        $this->view($args);
    }

    private function savePoll($poll)
    {
        $db = $this->registry->get('db');

        try {
            $db->beginTransaction();

            if (empty($poll['id'])) {
                $qry = $db->prepare('INSERT INTO poll (title, status) VALUES (?, ?)');
                $qry->execute([$poll['title'], 'draft']);
                $poll['id'] = $db->lastInsertId();
            } else {
                $qry = $db->prepare('UPDATE poll SET title = ? WHERE id = ?');
                $qry->execute([$poll['title'], $poll['id']]);

                $qry = $db->prepare('DELETE FROM question WHERE poll_id = ?');
                $qry->execute([$poll['id']]);
            }

            $qry = $db->prepare('INSERT INTO question (title, is_multiple, is_required, poll_id) VALUES (?, ?, ?, ?)');
            foreach ($poll['questions'] as $q) {
                $qry->execute([
                    $q['title'],
                    $q['is_multiple'],
                    isset($q['is_required']) ? true : false,
                    $poll['id'],
                ]);

                $question_id = $db->lastInsertId();

                $subqry = $db->prepare('INSERT INTO answer (title, question_id) VALUES (?, ?)');
                foreach ($q['answers'] as $a) {
                    $subqry->execute([
                        $a['title'],
                        $question_id,
                    ]);
                }
            }

            $db->commit();
        } catch (PDOException $e) {
            $db->rollBack();
            throw $e;
        }

        return $poll;
    }

    public function activate($params)
    {
        $this->activateOrClose('active', $params[0]);
    }

    public function close($params)
    {
        $this->activateOrClose('archived', $params[0]);
    }

    private function activateOrClose($action, $id)
    {
        $db = $this->registry->get('db');
        $qry = $db->prepare('UPDATE poll SET status = ? WHERE id = ?');
        $qry->execute([$action, $id]);

        header('Location: ' . HOME_URL . '/admin');
        die;
    }

    public function delete($params)
    {
        $db = $this->registry->get('db');
        $qry = $db->prepare('DELETE FROM poll WHERE id = ?');
        $qry->execute([$params[0]]);

        header('Location: ' . HOME_URL . '/admin');
        die;
    }

    public function results($params)
    {
        $db = $this->registry->get('db');
        $poll = PollRepository::getResults($params[0], $db);

        if (!$poll) {
            die('Wrong poll ID');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = filter_input_array(INPUT_POST);
            $data['questions'] = array_filter($data['questions'], function($var) {
                if (!isset($var['answers'])) {
                    return false;
                }
                return $var;
            });

            $i = 0;
            $users = [];
            $request = '';
            foreach ($data['questions'] as $k => $q) {
                $qMarks = str_repeat('?,', count($q['answers']) - 1) . '?';
                $qry = $db->prepare(
                        'SELECT DISTINCT v.user_sign'
                        . ' FROM vote v'
                        . ' LEFT JOIN answer a ON v.answer_id = a.id'
                        . ' LEFT JOIN question q ON a.question_id = q.id'
                        . ' WHERE q.id = ?'
                        . ' AND answer_id IN (' . $qMarks . ')'
                );
                $qry->execute(array_merge([$k], array_keys($q['answers'])));
                $q_users = $qry->fetchAll(PDO::FETCH_COLUMN);
                if ($i == 0) {
                    $users = $q_users;
                    $i++;
                } else {
                    $users = array_intersect($users, $q_users);
                }

                $request .= $q['title'] . ':';
                $n = 0;
                $count = count($q['answers']);
                foreach ($q['answers'] as $a) {
                    if ($n != 0) {
                        $request .= ' ИЛИ';
                    }
                    $request .= ' <b>"' . $a . '"</b>';
                    if ($n == $count - 1) {
                        $request .= '. ';
                    }
                    $n++;
                }
            }

            if ($users) {
                $sql = ' AND v.user_sign IN ("' . implode('","', $users) . '")';
                $filtered_poll = PollRepository::getResults($params[0], $db, $sql);
                foreach ($poll['questions'] as $qk => $q) {
                    foreach ($q['answers'] as $ak => $a) {
                        if (!isset($filtered_poll['questions'][$qk]['answers'][$ak])) {
                            $filtered_poll['questions'][$qk]['answers'][$ak] = [
                                'id' => $ak,
                                'title' => $a['title'],
                                'count' => 0,
                            ];
                            asort($filtered_poll['questions'][$qk]['answers']);
                        }
                    }
                }
            }
        }

        $this->view([
            'poll' => isset($filtered_poll) ? $filtered_poll : $poll,
            'request' => isset($request) ? $request : false,
            'no_users' => empty($users) ? true : false,
            'template' => 'admin/results',
        ]);
    }

}
