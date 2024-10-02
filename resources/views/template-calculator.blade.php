@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    <div class="calculator">
      <h2>GAMMA Blinds Fence Calculator</h2>
      
      <form id="blinds-calculator" action="{{ home_url('/wp-json/blinds-calculator/v1/calculate') }}" method="POST">
        @php(wp_nonce_field('wp_rest'))
        
        <div id="gamma" class="tab-content active">
          <div class="row">
            <input type="number" name="gamma[0][width]" placeholder="Panel Width (m)" step="0.01" required>
            <select name="gamma[0][height]" required>
              <option value="">Select Height (m)</option>
              <option value="0.85">0.85</option>
              <option value="1.01">1.01</option>
              <option value="1.17">1.17</option>
              <option value="1.33">1.33</option>
              <option value="1.49">1.49</option>
              <option value="1.65">1.65</option>
              <option value="1.81">1.81</option>
              <option value="1.97">1.97</option>
              <option value="2.13">2.13</option>
              <option value="2.29">2.29</option>
              <option value="2.45">2.45</option>
              <option value="2.61">2.61</option>
              <option value="2.77">2.77</option>
              <option value="2.93">2.93</option>
              <option value="3.09">3.09</option>
            </select>
            <input type="number" name="gamma[0][quantity]" placeholder="Number of Panels" required>
          </div>
          <button type="button" class="add-row" data-model="gamma">Add Row</button>
        </div>

        <button type="submit">Calculate Materials</button>
      </form>
      <div id="results"></div>
    </div>
  @endwhile
@endsection