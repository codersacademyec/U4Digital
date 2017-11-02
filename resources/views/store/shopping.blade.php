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
                                        <br>
                                        <br>
                                        <p>{{$product->description}}</p>
                                        <p> <b>Precio:</b>{{$product->price}} $</p>
                                        <p>Cantidad: <input type="number" id="number" name="number" required="required" data-validate-minmax="1,100"></p> 
                                        
                                        <button class="btn btn-success">
                                            <i class="fa fa-shopping-cart"></i> Añadir
                                        </button>
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
                    <h2>Carrito</h2>
                    <div class="clearfix"></div>
                    <div class="x_content">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                  <th>Cantidad</th>
                                  <th>Producto</th>
                                  <th>Precio</th>
                                </tr>
                              </thead>
                              <tfoot>
                                  <tr>
                                        <td></td>
                                        <td>Total</td>
                                  </tr>
                              </tfoot>
                              <tbody>
                                
                              </tbody>
                            </table>
                        <button class="btn btn-success pull-right" data-toggle="modal" data-target=".bs-example-modal-lg">
                            <i class="fa fa-credit-card"></i> Pagar
                        </button>
                        
                        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">

                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel2">Resúmen de compra</h4>
                                </div>
                                <div class="modal-body">
                                  <h4>Productos</h4>
                                  <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                                  <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <div style="position:relative;margin: 10px 10px;">
                                        <a href="https:&#x2F;&#x2F;payment.payphonetodoesposible.com&#x2F;/Pay/500/6190477d-a58c-4a97-86ce-73cfe61d5962/es/" 
                                        style="position: relative;
                                        padding: 8px 30px;
                                        border: 0;
                                        margin: 10px 1px;
                                        cursor: pointer;
                                        border-radius: 2px;
                                        text-transform: uppercase;
                                        text-decoration: none;
                                        color: rgba(255,255,255,.84) !important;
                                        transition: background-color .2s ease,box-shadow .28s cubic-bezier(.4,0,.2,1);
                                        outline: none!important;
                                        background-color:#ff9100;" onmouseover="this.style.backgroundColor='#ff8400'" onmouseout="this.style.backgroundColor='#ff9100'">Pagar con PayPhone</a>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>     
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