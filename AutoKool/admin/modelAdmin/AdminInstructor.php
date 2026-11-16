<?php
class AdminInstructor
{
public static function getAll()
{
    $db = new Database();

    return $db->getAll(
        "SELECT id, name, email, phone, description,
                picture, status
         FROM instructors
         ORDER BY name"
    );
}

    public static function exists($id)
    {
        return (bool)(new Database())->getOne('SELECT id FROM instructors WHERE id = ?', [$id]);
    }

    public static function getById($id)
    {
        return (new Database())->getOne(
            'SELECT id, name, email, phone, description, picture FROM instructors WHERE id = ?',
            [$id]
        );
    }
public static function getActive()
{
    $db = new Database();

    return $db->getAll(
        "SELECT id, name
         FROM instructors
         WHERE status = 'active'
         ORDER BY name"
    );
}
    public static function create($data)
    {
        return (new Database())->executeRun(
            'INSERT INTO instructors (name, email, phone, description, picture) VALUES (?, ?, ?, ?, ?)',
            [$data['name'], $data['email'], $data['phone'], $data['description'], $data['picture']]
        );
    }

    public static function update($id, $data)
    {
        if ($data['picture'] !== null) {
            return (new Database())->executeRun(
                'UPDATE instructors SET name=?, email=?, phone=?, description=?, picture=? WHERE id=?',
                [$data['name'], $data['email'], $data['phone'], $data['description'], $data['picture'], $id]
            );
        }
        return (new Database())->executeRun(
            'UPDATE instructors SET name=?, email=?, phone=?, description=? WHERE id=?',
            [$data['name'], $data['email'], $data['phone'], $data['description'], $id]
        );
    }

    public static function delete($id)
    {
        return (new Database())->executeRun('DELETE FROM instructors WHERE id = ?', [$id]);
    }

    public static function changeStatus($id, $status)
{
    if (!in_array($status, ['active', 'inactive'], true)) {
        return false;
    }

    $db = new Database();

    return $db->executeRun(
        "UPDATE instructors
         SET status = ?
         WHERE id = ?",
        [$status, $id]
    );
}
}
