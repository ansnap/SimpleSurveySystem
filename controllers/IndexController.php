<?php

/**
 * Description of index
 */
class IndexController extends BaseController
{

    public function index()
    {
        $db = $this->registry->get('db');
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = filter_input_array(INPUT_POST);

            foreach ($data['questions'] as $q) {
                if ($q['is_required'] && !isset($q['answers'])) {
                    $errors['required'] = 'Получены ответы не на все обязательные вопросы';
                }
            }

            if (!$errors) {
                $qry = $db->prepare('INSERT INTO vote (answer_id, user_sign) VALUES (?, ?)');
                $uniq_id = uniqid();
                foreach ($data['questions'] as $q) {
                    if (isset($q['answers'])) {
                        foreach ($q['answers'] as $a) {
                            $qry->execute([$a, $uniq_id]);
                        }
                    }
                }

                header('Location: ' . HOME_URL . '/index/results/' . $data['id']);
                die;
            }
        }
        $qry = $db->prepare('SELECT id FROM poll WHERE status = ?');
        $qry->execute(['active']);
        $poll_id = $qry->fetch(PDO::FETCH_COLUMN);

        $poll = PollRepository::getPoll($poll_id, $db);

        $this->view([
            'poll' => $poll,
            'errors' => $errors,
            'template' => 'index',
        ]);
    }

    public function results($params)
    {
        $db = $this->registry->get('db');
        $poll = PollRepository::getResults($params[0], $db);

        if (!$poll) {
            die('Wrong poll ID');
        }

        $this->view([
            'poll' => $poll,
            'template' => 'results',
        ]);
    }

}
