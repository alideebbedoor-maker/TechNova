<?php

namespace App\Repositories;

interface ArticleRepositoryInterface
{
    public function getPublishedArticles();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
}