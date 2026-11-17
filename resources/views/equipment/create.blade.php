@extends('layouts.app')

@section('title', 'Add equipment')

@section('content')
    <main class="container equipment-form-page">
        <div class="hero form-hero">
            <div>
                <div class="eyebrow">Inventory control / New item</div>
                <h1 class="title">Add equipment</h1>
                <p class="muted">Add an item to your inventory with its price, category, and availability.</p>
            </div>
            <a class="btn ghost" href="{{ route('equipment.index') }}"><span aria-hidden="true">←</span> Back to equipment</a>
        </div>

        @if ($errors->any())
            <div class="form-error-summary" role="alert">
                <strong>Please check the highlighted fields.</strong>
                <span>{{ $errors->count() }} {{ $errors->count() === 1 ? 'field needs' : 'fields need' }} your attention.</span>
            </div>
        @endif

        <section class="card equipment-form-card" aria-labelledby="equipment-details-heading">
            <div class="form-card-heading">
                <div class="form-card-icon" aria-hidden="true">＋</div>
                <div>
                    <h2 id="equipment-details-heading">Equipment details</h2>
                    <p>Fields marked <span class="required-mark">*</span> are required.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('equipment.store') }}">
                @csrf
                @include('equipment.form')

                <div class="form-footer">
                    <a class="btn ghost" href="{{ route('equipment.index') }}">Cancel</a>
                    <button class="btn" type="submit"><span aria-hidden="true">＋</span> Save equipment</button>
                </div>
            </form>
        </section>
    </main>
@endsection
