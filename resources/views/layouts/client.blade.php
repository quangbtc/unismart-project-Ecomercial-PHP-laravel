<!DOCTYPE html>
<html>

<head>
    <title>ISMART STORE</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('client/css/bootstrap/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/reset.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/carousel/owl.theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/css/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/plugins/css/all.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('client/responsive.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/detail_order.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('client/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/elevatezoom-master/jquery.elevatezoom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/bootstrap/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('client/js/carousel/owl.carousel.js') }}" type="text/javascript"></script>

    <script src="{{ asset('client/js/main.js') }}" type="text/javascript"></script>

</head>

<body>
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <div id="main-menu" class="fl-left">
                            <ul class="list-item clearfix">
                                <li class="item">
                                    <a href="{{ getConfig('facebook') }}" title="" id="facebook" class="fl-left">
                                        <span class="text-primary" style="font-size: 22px"><i
                                                class="fab fa-facebook-square"></i></span></a>
                                </li>
                                  <li class="item">
                                    <a href="{{ getConfig('telegram') }}" title="" id="telegram" class="fl-left">
                                        <span class="text-primary" style="font-size: 22px"><i class="fab fa-telegram-plane"></i></span></a>
                                </li>
                                <li class="item">
                                    <a href="{{ getConfig('youtube') }}" title="" id="telegram" class="fl-left">
                                        <span class="text-primary" style="font-size: 22px"><i class="fab fa-youtube"></i></span></a>
                                </li>
                            </ul>
                        </div>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="{{ url('/') }}" title="">Trang ch???</a>
                                </li>
                                <li>
                                    <a href="{{ route('client.product.index') }}" title="">S???n ph???m</a>
                                </li>
                                <li>
                                    <a href="{{ route('client.post.index') }}" title="">Tin t???c</a>
                                </li>
                                <li>
                                    <a href="{{route('page.about_us',getAboutUs('Gi???i thi???u'))}}" title="">Gi???i thi???u</a>
                                </li>
                                <li>
                                    <a href="{{route('page.contact',getAboutUs('Li??n h???'))}}" title="">Li??n h???</a>
                                </li>
                                @if (session('user_login'))
                                    <li class="dropdown">
                                        <div class=" dropdown-toggle text-white pt-2 fs-3" type=""
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ session('user_login') }}
                                        </div>
                                        <div class="dropdown-menu " aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item text-dark" href="{{ route('cart.list') }}">????n
                                                h??ng
                                                m???i</a>
                                            <a class="dropdown-item text-dark"
                                                href="{{ route('order.history', session('user_login')) }}">L???ch s???
                                                mua
                                                h??ng</a>
                                            <a class="dropdown-item text-dark"
                                                href="{{ route('customer.logout') }}">????ng xu???t</a>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="?page=home" title="" id="logo" class="fl-left"><img
                                src="{{ asset('client/images/logo.png') }}" /></a>
                        <div id="search-wp" class="fl-left">
                            <form method="POST" action="{{ route('search') }}">
                                @csrf
                                <input type="text" name="keywords" id="s" placeholder="Nh???p s???n ph???m t??m ki???m">
                                <input type="submit" id="sm-s" name="btn btn-default btn_search" value="T??m ki???m">
                            </form>
                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">T?? v???n</span>
                                <span class="phone">{{getConfig('phone')}}</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
                            <a href="{{ route('cart.checkout') }}" title="gi??? h??ng" id="cart-respon-wp"
                                class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num">2</span>
                            </a>
                            <div id="cart-wp" class="fl-right">
                                <div id="btn-cart">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span id="num">{{ Cart::count() }}</span>
                                </div>

                                <div id="dropdown">
                                    <p class="desc">C?? <span>{{ Cart::count() }} s???n ph???m</span> trong gi??? h??ng</p>
                                    @if (Cart::content()->count() > 0)
                                        <ul class="list-cart">
                                            @foreach (Cart::content() as $row)
                                                <li class="clearfix">
                                                    <a href="" title="" class="thumb fl-left">
                                                        <img src="{{ url($row->options->thumbnail) }}" alt="">
                                                    </a>
                                                    <div class="info fl-right">
                                                        <a href="" title="" class="product-name">Sony Express X6</a>
                                                        <p class="price">
                                                            {{ number_format($row->price, 0, '', '.') . ' ??' }}
                                                        </p>
                                                        <p class="qty">S??? l?????ng: <span>{{ $row->qty }}</span></p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="total-price clearfix">
                                            <p class="title fl-left">T???ng:</p>
                                            <p class="price fl-right">{{ Cart::total() }}</p>
                                        </div>
                                        <dic class="action-cart clearfix">
                                            <a href="{{ route('cart.checkout') }}" title="Gi??? h??ng"
                                                class="view-cart fl-left">Gi??? h??ng</a>
                                            <a href="{{ route('cart.restore') }}" title="Thanh to??n"
                                                class="checkout fl-right">Thanh
                                                to??n</a>
                                        </dic>

                                </div>
                            @else
                                <div class="alert alert-warning">Gi??? h??ng tr???ng</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
            <div id="footer-wp">
                <div id="foot-body">
                    <div class="wp-inner clearfix">
                        <div class="block" id="info-company">
                            <h3 class="title">ISMART</h3>
                            <p class="desc">ISMART lu??n cung c???p lu??n l?? s???n ph???m ch??nh h??ng c?? th??ng tin r?? r??ng, ch??nh
                                s??ch ??u ????i c???c l???n cho kh??ch h??ng c?? th??? th??nh vi??n.</p>
                            <div id="payment">
                                <div class="thumb">
                                    <img src="public/images/img-foot.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="block menu-ft" id="info-shop">
                            <h3 class="title">Th??ng tin c???a h??ng</h3>
                            <ul class="list-item">
                                <li>
                                    <p>{{getConfig('address')}}</p>
                                </li>
                                <li>
                                    <p>{{getConfig('phone')}}</p>
                                </li>
                                <li>
                                    <p>{{getConfig('email')}}</p>
                                </li>
                            </ul>
                        </div>
                        <div class="block menu-ft policy" id="info-shop">
                            <h3 class="title">Ch??nh s??ch mua h??ng</h3>
                            <ul class="list-item">
                                <li>
                                    <a href="" title="">Quy ?????nh - ch??nh s??ch</a>
                                </li>
                                <li>
                                    <a href="" title="">Ch??nh s??ch b???o h??nh - ?????i tr???</a>
                                </li>
                                <li>
                                    <a href="" title="">Ch??nh s??ch h???i vi???n</a>
                                </li>
                                <li>
                                    <a href="" title="">Giao h??ng - l???p ?????t</a>
                                </li>
                            </ul>
                        </div>
                        <div class="block" id="newfeed">
                            <h3 class="title">B???ng tin</h3>
                            <p class="desc">????ng k?? v???i chung t??i ????? nh???n ???????c th??ng tin ??u ????i s???m nh???t</p>
                            <div id="form-reg">
                                <form method="POST" action="">
                                    <input type="email" name="email" id="email" placeholder="Nh???p email t???i ????y">
                                    <button type="submit" id="sm-reg">????ng k??</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="foot-bot">
                    <div class="wp-inner">
                        <p id="copyright">?? B???n quy???n thu???c v??? unitop.vn | Php Master</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="menu-respon">
            <a href="?page=home" title="" class="logo">VSHOP</a>
            <div id="menu-respon-wp">
                <ul class="" id="main-menu-respon">
                    <li>
                        <a href="?page=home" title>Trang ch???</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title>??i???n tho???i</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?page=category_product" title="">Iphone</a>
                            </li>
                            <li>
                                <a href="?page=category_product" title="">Samsung</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="?page=category_product" title="">Iphone X</a>
                                    </li>
                                    <li>
                                        <a href="?page=category_product" title="">Iphone 8</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="?page=category_product" title="">Nokia</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="?page=category_product" title>M??y t??nh b???ng</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title>Laptop</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title>????? d??ng sinh ho???t</a>
                    </li>
                    <li>
                        <a href="?page=blog" title>Blog</a>
                    </li>
                    <li>
                        <a href="#" title>Li??n h???</a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="btn-top"><img src="{{ asset('client/images/icon-to-top.png') }}" alt="" /></div>
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
        </script>

</body>

</html>
