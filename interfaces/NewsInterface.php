<?php

interface NewsInterface
{
    public function create($userId, $title, $description, $image);

    public function update($id, $title, $text);

    public function delete($id);

    public function getAll();

    public function getById($id);
}