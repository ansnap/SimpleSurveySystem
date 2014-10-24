<?php

class PollRepository
{

    public static function getPoll($id, $db)
    {
        if (!$id) {
            return null;
        }

        $poll = [];
        $qry = $db->prepare(
                'SELECT p.id p_id, p.title p_title, p.status p_status,'
                . ' q.id q_id, q.title q_title, q.is_multiple q_is_multiple, q.is_required q_is_required,'
                . ' a.id a_id, a.title a_title'
                . ' FROM poll p'
                . ' LEFT JOIN question q ON q.poll_id = p.id'
                . ' LEFT JOIN answer a ON a.question_id = q.id'
                . ' WHERE p.id = ?'
        );
        $qry->execute([$id]);
        $qry->fetchAll(PDO::FETCH_FUNC, function($p_id, $p_title, $p_status, $q_id, $q_title, $q_is_multiple, $q_is_required, $a_id, $a_title) use (&$poll) {
            if (!isset($poll['title']) || !isset($poll['title']) || !isset($poll['status'])) {
                $poll = [
                    'id' => $p_id,
                    'title' => $p_title,
                    'status' => $p_status,
                ];
            }
            if (!isset($poll['questions'][$q_id])) {
                $poll['questions'][$q_id] = [
                    'id' => $q_id,
                    'title' => $q_title,
                    'is_multiple' => $q_is_multiple,
                    'is_required' => $q_is_required,
                ];
            }
            $poll['questions'][$q_id]['answers'][$a_id] = [
                'id' => $a_id,
                'title' => $a_title,
            ];
        });

        return $poll;
    }

    public static function getResults($id, $db, $sql = '')
    {
        if (!$id) {
            return null;
        }

        $poll = [];

        $qry = $db->prepare(
                'SELECT a.id a_id, a.title a_title, q.id q_id, q.title q_title, p.title p_title, COUNT(v.id) v_count'
                . ' FROM answer a'
                . ' LEFT JOIN question q ON a.question_id = q.id'
                . ' LEFT JOIN poll p ON q.poll_id = p.id'
                . ' LEFT JOIN vote v ON v.answer_id = a.id'
                . ' WHERE p.id = ?'
                . $sql
                . ' GROUP BY a.id'
        );
        $qry->execute([$id]);
        $qry->fetchAll(PDO::FETCH_FUNC, function($a_id, $a_title, $q_id, $q_title, $p_title, $v_count) use (&$poll) {
            if (!isset($poll['title'])) {
                $poll['title'] = $p_title;
            }
            if (!isset($poll['questions'][$q_id])) {
                $poll['questions'][$q_id] = [
                    'id' => $q_id,
                    'title' => $q_title,
                ];
            }
            $poll['questions'][$q_id]['answers'][$a_id] = [
                'id' => $a_id,
                'title' => $a_title,
                'count' => $v_count,
            ];
        });

        $qry = $db->prepare(
                'SELECT q.id q_id, COUNT(DISTINCT v.user_sign) v_count'
                . ' FROM question q'
                . ' LEFT JOIN poll p ON q.poll_id = p.id'
                . ' LEFT JOIN answer a ON a.question_id = q.id'
                . ' LEFT JOIN vote v ON v.answer_id = a.id'
                . ' WHERE p.id = ?'
                . $sql
                . ' GROUP BY q.id'
        );
        $qry->execute([$id]);
        $qry->fetchAll(PDO::FETCH_FUNC, function($q_id, $v_count) use (&$poll) {
            $poll['questions'][$q_id]['count'] = $v_count;
        });

        return $poll;
    }

}
