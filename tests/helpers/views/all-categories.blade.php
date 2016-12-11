<ul>
@foreach($categories as $category)
<li>{{$category->title}} ({{$category->postsCount}})</li>
@endforeach
</ul>