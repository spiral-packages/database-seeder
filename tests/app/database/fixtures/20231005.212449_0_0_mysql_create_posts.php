<?php

declare(strict_types=1);

namespace app\database\fixtures;

use Cycle\Migrations\Migration;

class OrmMysql85ecb218ba51e1f7bee67c3d0d11b308 extends Migration
{
    protected const DATABASE = 'mysql';

    public function up(): void
    {
        $this->table('posts')
            ->addColumn('id', 'primary', ['nullable' => false, 'default' => null])
            ->addColumn('published_at', 'datetime', ['nullable' => false, 'default' => null])
            ->addColumn('content', 'text', ['nullable' => false, 'default' => null])
            ->addColumn('author_id', 'integer', ['nullable' => false, 'default' => null])
            ->addIndex(['author_id'], ['name' => 'posts_index_author_id_651f29a188f7f', 'unique' => false])
            ->setPrimaryKeys(['id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('posts')->drop();
    }
}
