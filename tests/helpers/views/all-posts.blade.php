@foreach($posts as $post)
    <div>
        <h1>{{$post->title}}</h1>
        <p>{{$post->summary}}</p>
    </div>
@endforeach