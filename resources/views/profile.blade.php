@extends('layouts.app')

@section('content')
	<script>
		 function like() {
			$.ajax({
			   type:'POST',
			   url: '{{route("like",$user->id)}}',
			   data: {
				   "_token" : "{{ csrf_token() }}",
				   "likeable_id" : "{{$user->id}}"
			   },
			   success:function(data) {
				  console.log(data);
				  $(".like").html(data.likemsg);
			   }
			});
		 }
	</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
					{{$user->name}}
					@auth
						<a href="#" class="like" onclick="like()" >{{$likestate}}<a>
					@endauth
				</div>

                <div class="card-body">
					<div class="row">
						<div class="col-md-12"><img src="{{ asset('storage/uploads/images/'.$user->profile_image) }}" class="" width="100" height="100"  alt=""></div>
						@auth
						<div class="col-md-6">{{ $user->first_name }}</div><div class="col-md-6 ">{{ $user->last_name }}</div>
						<div class="col-md-12">{{ $user->email }}</div>
						<div class="col-md-12">{{ $user->address }}</div>
						<div class="col-md-12">{{ $user->relation_status }}</div>
						@endauth
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
