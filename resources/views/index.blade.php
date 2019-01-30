<html>
	<header></header>
	<body>
		<span></span>

		@foreach($users as $user)
		<ul>
			<li>{{$user->id}}</li>
			<li>{{$user->email}}</li>
			<li>{{$user->name}}</li>
		</ul>
		@endforeach

	</body>
</html>
