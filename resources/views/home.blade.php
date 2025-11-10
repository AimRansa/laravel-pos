@extends('layouts.admin')
@section('content-header', __('dashboard.title'))
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-lg-3 col-6">
         <!-- small box -->
         <div class="small-box bg-info">
            <div class="inner">
               <h3>{{$orders_count}}</h3>
               <p>{{ __('dashboard.Orders_Count') }}</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
            <a href="{{route('orders.index')}}" class="small-box-footer">{{ __('common.More_info') }} <i class="fas fa-arrow-circle-right"></i></a>
         </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
         <!-- small box -->
         <div class="small-box bg-success">
            <div class="inner">
               <h3>{{config('settings.currency_symbol')}} {{number_format($income, 2)}}</h3>
               <p>{{ __('dashboard.Income') }}</p>
            </div>
            <div class="icon">
               <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{route('orders.index')}}" class="small-box-footer">{{ __('common.More_info') }} <i class="fas fa-arrow-circle-right"></i></a>
         </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
         <!-- small box -->
         <div class="small-box bg-danger">
            <div class="inner">
               <h3>{{config('settings.currency_symbol')}} {{number_format($income_today, 2)}}</h3>
               <p>{{ __('dashboard.Income_Today') }}</p>
            </div>
            <div class="icon">
               <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{route('orders.index')}}" class="small-box-footer">{{ __('common.More_info') }} <i class="fas fa-arrow-circle-right"></i></a>
         </div>
      </div>
   </div>
</div>

<div class="container-fluid">
   <div class="row">
      {{-- LOW STOCK PRODUCTS --}}
<div class="col-6 my-2">
   <h3>Low Stock Product</h3>
   <section class="content">
      <div class="card product-list">
         <div class="card-body">
            <table class="table">
               <thead>
                  <tr>
                     <th>ID Produk</th>
                     <th>Nama Stok</th>
                     <th>Jumlah Stok</th>
                     <th>Satuan</th>
                     <th>Tanggal Masuk</th>
                     <th>Tanggal Expired</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse ($low_stock_products as $product)
                  <tr>
                     <td>{{$product->id_produk}}</td>
                     <td>{{$product->nama_stok}}</td>
                     <td>
                        <span class="badge badge-{{ $product->jumlah_stok < 20 ? 'danger' : 'warning' }}">
                           {{$product->jumlah_stok}}
                        </span>
                     </td>
                     <td>{{$product->satuan}}</td>
                     <td>
                        @if($product->tanggal_masuk instanceof \Carbon\Carbon)
                           {{$product->tanggal_masuk->format('d/m/Y')}}
                        @else
                           {{\Carbon\Carbon::parse($product->tanggal_masuk)->format('d/m/Y')}}
                        @endif
                     </td>
                     <td>
                        @php
                           $expired = $product->tanggal_expired instanceof \Carbon\Carbon 
                              ? $product->tanggal_expired 
                              : \Carbon\Carbon::parse($product->tanggal_expired);
                           $isExpired = $expired->isPast();
                        @endphp
                        <span class="badge badge-{{ $isExpired ? 'danger' : 'info' }}">
                           {{$expired->format('d/m/Y')}}
                        </span>
                     </td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="6" class="text-center">Semua stok aman</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </section>
</div>

      {{-- HOT PRODUCTS (Current Month) --}}
      <div class="col-6 my-2">
         <h3>Hot Products (This Month)</h3>
         <section class="content">
            <div class="card product-list">
               <div class="card-body">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>ID Menu</th>
                           <th>Nama Menu</th>
                           <th>Total Terjual</th>
                           <th>Total Pendapatan</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse ($current_month_products as $product)
                        <tr>
                           <td>{{$product->id_menu}}</td>
                           <td>{{$product->nama_menu}}</td>
                           <td>
                              <span class="badge badge-success">{{$product->total_terjual}}</span>
                           </td>
                           <td>{{config('settings.currency_symbol')}} {{number_format($product->total_pendapatan, 0, ',', '.')}}</td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="4" class="text-center">Belum ada penjualan bulan ini</td>
                        </tr>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            </div>
         </section>
      </div>

      {{-- HOT PRODUCTS (Last 6 Months) --}}
      <div class="col-6 my-4">
         <h3>Hot Products (Last 6 Months)</h3>
         <section class="content">
            <div class="card product-list">
               <div class="card-body">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>ID Menu</th>
                           <th>Nama Menu</th>
                           <th>Harga</th>
                           <th>Total Terjual</th>
                           <th>Total Pendapatan</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse ($hot_products as $product)
                        <tr>
                           <td>{{$product->id_menu}}</td>
                           <td>{{$product->nama_menu}}</td>
                           <td>{{config('settings.currency_symbol')}} {{number_format($product->harga, 0, ',', '.')}}</td>
                           <td>
                              <span class="badge badge-primary">{{$product->total_terjual}}</span>
                           </td>
                           <td>{{config('settings.currency_symbol')}} {{number_format($product->total_pendapatan, 0, ',', '.')}}</td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="5" class="text-center">Belum ada data penjualan</td>
                        </tr>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            </div>
         </section>
      </div>

      {{-- BEST SELLING PRODUCTS (This Year) --}}
      <div class="col-6 my-4">
         <h3>Best Selling Products (This Year)</h3>
         <section class="content">
            <div class="card product-list">
               <div class="card-body">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>ID Menu</th>
                           <th>Nama Menu</th>
                           <th>Harga</th>
                           <th>Total Terjual</th>
                           <th>Total Pendapatan</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse ($best_selling_products as $product)
                        <tr>
                           <td>{{$product->id_menu}}</td>
                           <td>{{$product->nama_menu}}</td>
                           <td>{{config('settings.currency_symbol')}} {{number_format($product->harga, 0, ',', '.')}}</td>
                           <td>
                              <span class="badge badge-success">{{$product->total_terjual}}</span>
                           </td>
                           <td>{{config('settings.currency_symbol')}} {{number_format($product->total_pendapatan, 0, ',', '.')}}</td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="5" class="text-center">Belum ada penjualan tahun ini</td>
                        </tr>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>
@endsection