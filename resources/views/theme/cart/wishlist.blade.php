@extends('cart.layouts.default')

@section('page')

<div class="page-header">
	<h1>Wishlist? No problem!</h1>
	<p class="lead">Cart supports multiple cart instances, so that you can have as many shopping cart instances on the same application without any conflicts.</p>
</div>

<div class="row">
	<div class="col-lg-12">

		<div class="table-responsive">

			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="col-md-7">Name</td>
						<td class="col-md-1">Price</td>
						<td class="col-md-2" colspan="2">Total</td>
					</tr>
				</thead>
				<tbody>
					@if ($items->isEmpty())
					<tr>
						<td colspan="3">Your wishlist is empty.</td>
					</tr>
					@else
					@foreach ($items as $item)
					<tr>
						<td>
							<div class="col-md-2">
								<img src="http://placehold.it/80x80" alt="..." class="img-thumbnail">
							</div>
							{{{ $item->get('name') }}}
						</td>
						<td>{{{ Converter::value($item->get('price'))->from('currency.eur')->to('currency.usd')->format() }}}</td>
						<td>
							{{{ Converter::value($item->subTotal())->from('currency.eur')->to('currency.usd')->format() }}}
						</td>
						<td>
							<a class="btn btn-danger btn-xs" href="{{ URL::to("wishlist/{$item->get('rowId')}/remove") }}">Delete</a>
							<a class="btn btn-info btn-xs" href="{{ URL::to("wishlist/{$item->get('rowId')}/move") }}">To Cart</a>
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>

@if ( ! $items->isEmpty())
<a href="{{ URL::to('wishlist/destroy') }}" class="btn btn-danger">Empty Wishlist</a>
@endif

<br>
@stop
