@include('layout.header')
		@include('layout.menu')
		<section class="content">
			<div class="container-fluid">
				@yield('konten')
			</div>
		</section>
		@include('layout.footer')