<?php
use duncan3dc\Laravel\BladeInstance;
use League\Flysystem\Filesystem;
use WackyStudio\Flatblog\Builders\PostsBuilder;
use WackyStudio\Flatblog\Factories\PostEntityFactory;
use WackyStudio\Flatblog\Factories\RawEntityFactory;
use WackyStudio\Flatblog\Templates\TemplateRenderer;

class PostBuilderTest extends TestCase
{
    /**
    *@test
    */
    public function it_builds_single_posts()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
          return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], [
            'single' => 'single-post'
        ]);

        $expectedContentForBackendPost = implode(PHP_EOL, [
            '<h1>Do you really need a backend for that?</h1>',
            '<h2>Hello World 2</h2>'
        ]);

        $expectedContentForFrontendPost = implode(PHP_EOL, [
            '<h1>Sass tricks you should know!</h1>',
            '<h2>Hello World</h2>'
        ]);

        $singlePosts = $postBuilder->buildSinglePosts();

        $this->assertEquals($expectedContentForBackendPost, $singlePosts['posts/backend/do-you-really-need-a-backend-for-that']);
        $this->assertEquals($expectedContentForFrontendPost, $singlePosts['posts/frontend/sass-tricks-you-should-know']);

    }

    /**
    *@test
    */
    public function it_builds_page_with_list_of_all_post_and_sorts_them_descending_by_date()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], [
            'all-posts' => 'all-posts'
        ]);

        $expectedContent = implode(PHP_EOL, [
            '<div>',
            '        <h1>Sass tricks you should know!</h1>',
            '        <p>This is a summary</p>',
            '    </div>',
            '    <div>',
            '        <h1>Do you really need a backend for that?</h1>',
            '        <p>This is a summary</p>',
            '    </div>',
            ''
        ]);

        $result = $postBuilder->buildPostsList();

        $this->assertEquals($expectedContent, $result);
    }

    /**
    *@test
    */
    public function it_builds_pages_for_each_category_with_posts_and_sorts_them_alphabetically()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], [
            'single-category' => 'single-category'
        ]);

        $expectedContentBackend = implode(PHP_EOL, [
           '<h1>Backend</h1>',
           '<div>',
           '<h1>Do you really need a backend for that?</h1>',
           '<p>This is a summary</p>',
           '</div>',
           ''
        ]);

        $expectedContentFrontend = implode(PHP_EOL, [
            '<h1>Frontend</h1>',
            '<div>',
            '<h1>Sass tricks you should know!</h1>',
            '<p>This is a summary</p>',
            '</div>',
            ''
        ]);

        $categories = $postBuilder->buildSingleCategories();

        $this->assertEquals($expectedContentBackend, $categories[0]);
        $this->assertEquals($expectedContentFrontend, $categories[1]);
    }
    
    /**
    *@test
    */
    public function it_builds_list_of_all_categories()
    {
        $fileSystem = $this->createVirtualFilesystemForPosts();
        $this->app->getContainer()[Filesystem::class] = function() use($fileSystem){
            return $fileSystem;
        };
        $rawEntities = ($this->app->getContainer()[RawEntityFactory::class])->getEntitiesForDirectory('posts');

        $this->app->getContainer()[TemplateRenderer::class] = function(){
            $path = __DIR__ . '/../helpers/views';
            $cache = __DIR__ . '/../temp';
            return new TemplateRenderer(new BladeInstance($path, $cache));
        };

        $postBuilder = new PostsBuilder($rawEntities, $this->app->getContainer()[PostEntityFactory::class], $this->app->getContainer()[TemplateRenderer::class], [
            'all-categories' => 'all-categories'
        ]);

        $expectedContent = implode(PHP_EOL, [
            '<ul>',
            '<li>Backend (1)</li>',
            '<li>Frontend (1)</li>',
            '</ul>'
        ]);

        $categoryList = $postBuilder->buildCategoryList();

        $this->assertEquals($expectedContent, $categoryList);
    }
}