<?php

use Illuminate\Database\Seeder;

use App\Models\User\Article;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $article_count = random_int(100,250);
        while($article_count > 0) {
            $article = Article::create([
                'id'              => '',
                'organization_id' => '',
                'user_id'         => '',
                'cover'           => '',
                'cover_thumbnail' => '',
            ]);

            $article->translations()->create([
                'iso'         => '',
                'name'        => '',
                'description' => '',
                'vernacular'  => '',
            ]);

            $article->tags()->create([
                'iso'         => '',
                'tag'         => '',
                'name'        => '',
                'description' => '',
            ]);
        }
    }
}
