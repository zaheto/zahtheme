{{--
  Template Name: Calculator
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    <div class="calculator">
      <ul class="tabs">
        <li><a href="#gamma">GAMMA</a></li>
        <li><a href="#atlas">ATLAS</a></li>
        <li><a href="#sigma">SIGMA</a></li>
        <li><a href="#piramida">PIRAMIDA</a></li>
        <li><a href="#terra">TERRA</a></li>
      </ul>

      <form id="blinds-calculator" action="{{ home_url('/wp-json/blinds-calculator/v1/calculate') }}" method="POST">
        @php(wp_nonce_field('wp_rest'))
        <div id="gamma" class="tab-content">
          <div class="row">
            <input type="number" name="gamma[0][width]" placeholder="Panel Width (m)" step="0.01" required>
            <select name="gamma[0][height]" required>
              <option value="">Select Height</option>
              <!-- Add height options here -->
            </select>
            <input type="number" name="gamma[0][quantity]" placeholder="Number of Panels" required>
          </div>
          <button type="button" class="add-row" data-model="gamma">Add Row</button>
        </div>

        <!-- Repeat similar structure for ATLAS, SIGMA, PIRAMIDA -->

        <div id="terra" class="tab-content">
          <div class="row">
            <input type="number" name="terra[0][width]" placeholder="Panel Width (m)" step="0.01" required>
            <input type="number" name="terra[0][height]" placeholder="Panel Height (m)" step="0.01" required>
            <input type="number" name="terra[0][cassette_distance]" placeholder="Distance Between Cassettes (cm)" required>
            <input type="number" name="terra[0][base_distance]" placeholder="Base distance (cm)" required>
            <input type="number" name="terra[0][quantity]" placeholder="Number of Panels" required>
            <input type="number" name="terra[0][optimal_height]" placeholder="Optimal Height (m)" step="0.01" required>
          </div>
          <button type="button" class="add-row" data-model="terra">Add Row</button>
        </div>

        <button type="submit">Calculate Materials</button>
      </form>

      <div id="results"></div>
    </div>
  @endwhile
@endsection