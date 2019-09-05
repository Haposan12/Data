@extends('layouts.app')

@section('content')


<div class="search" style="margin-top: 50px">

    <h3 style="text-align: center;">Stemming Bahasa Indonesia</h3>
    <br>

    <form method="POST" enctype="multipart/form-data" action="{{ route('find') }}">
        {{ csrf_field() }}
        <legend></legend>
        <div style="margin-left: 500px">
            Silahkan pilih artikel anda (harus file .docx)<br>
            <input type="file" name="dokumen">
            <br>
            <input type="submit" name="submit" value="Pilih">    
        </div>
        
    </form>

</div>