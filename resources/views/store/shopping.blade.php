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
                                <li class="active"><a href="#home" data-toggle="tab">Pack 5 post</a>
                                </li>
                                <li><a href="#profile" data-toggle="tab">Pack 10 post</a>
                                </li>
                                <li><a href="#messages" data-toggle="tab">Vídeo de 15 seg</a>
                                </li>
                                <li><a href="#settings" data-toggle="tab">Vídeo de 30 seg</a>
                                </li>
                          </ul>
                        </div>

                        <div class="col-xs-9">
                            <!-- Tab panes -->
                             <div class="tab-content">
                                <div class="tab-pane active" id="home">
                                    <p class="lead">Pack de 5 Post</p>
                                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terr.</p>
                                </div>
                                <div class="tab-pane" id="profile">Profile Tab.</div>
                                <div class="tab-pane" id="messages">Messages Tab.</div>
                                <div class="tab-pane" id="settings">Settings Tab.</div>
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