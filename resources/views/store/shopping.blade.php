@extends('store.layouts.store')

@section('content')
    <!-- page content -->
    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Productos</h2>
                    <div class="clearfix"></div>
                    <div class="x_content">
                        
                            <br>

                            <div class="col-xs-3">
                                <ul class="nav nav-tabs tabs-left">
                                    @foreach($products as $key => $product)
                                    <li  class="{{ $key == 0 ? 'active' : '' }}"><a href="#{{$key}}" data-toggle="tab">{{$product->name}}</a>
                                    </li>
                                    @endforeach
                              </ul>
                            </div>

                            <div class="col-xs-9">
                                <!-- Tab panes -->
                                 <div class="tab-content">
                                    @foreach($products as $key => $product)
                                    <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="{{$key}}">
                                        <p class="lead">{{$product->name}}</p>
                                        <video width="400" controls>
                                            <source src="{{$product->video_route}}" type="video/mp4">
                                            Your browser does not support HTML5 video.
                                        </video>
                                        <p>{{$product->description}}</p>
                                        <p> <b>Precio:</b>{{$product->price}} $</p>
                                        <p>Cantidad: <input type="number" id="number" name="number" required="required" data-validate-minmax="1,100"></p> 
                                        
                                        <button class="btn btn-success">Añadir</button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Carrito de compra</h2>
                    <div class="clearfix"></div>
                    <div class="x_content">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/dashboard.js')) }}
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/dashboard.css')) }}
@endsection