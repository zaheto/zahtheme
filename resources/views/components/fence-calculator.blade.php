{{-- resources/views/components/fence-calculator.blade.php --}}
@props([
    'model' => 'atlas', // default model
    'showTabs' => true, // whether to show model selection tabs
])

<div class="fence-calculator">
    @if($showTabs)
    <div class="tab-buttons">
        <button type="button" class="tab-button {{ $model === 'atlas' ? 'active' : '' }}" data-model="atlas">ATLAS Model</button>
        <button type="button" class="tab-button {{ $model === 'gamma' ? 'active' : '' }}" data-model="gamma">GAMMA Model</button>
        <button type="button" class="tab-button {{ $model === 'sigma' ? 'active' : '' }}" data-model="sigma">SIGMA Model</button>
        <button type="button" class="tab-button {{ $model === 'piramida' ? 'active' : '' }}" data-model="piramida">PIRAMIDA Model</button>
        <button type="button" class="tab-button {{ $model === 'terra' ? 'active' : '' }}" data-model="terra">TERRA Model</button>
    </div>
    @endif

    <div id="atlas-calculator" class="calculator-tab {{ $model === 'atlas' ? 'active' : '' }}">
        <h2>Fence Panel Calculator - ATLAS Model</h2>
        <form id="atlas-fence-calculator" class="fence-calculator-form">
            <div class="form-group">
                <label for="atlas-panel-width">Panel Width (m):</label>
                <input type="number" 
                       id="atlas-panel-width" 
                       name="atlas-panel-width" 
                       step="0.01" 
                       min="0.3" 
                       max="3.3" 
                       required>
            </div>

            <div class="form-group">
                <label for="atlas-panel-height">Panel Height (m):</label>
                <select id="atlas-panel-height" name="atlas-panel-height" required>
                    @foreach(range(0.745, 3.145, 0.1) as $height)
                        <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="atlas-number-of-panels">Number of Panels:</label>
                <input type="number" 
                       id="atlas-number-of-panels" 
                       name="atlas-number-of-panels" 
                       min="1" 
                       required>
            </div>

            <button type="button" id="atlas-calculate" class="calculate-button">Calculate</button>
        </form>

        <div class="results-section">
            <h3>Required Materials for ATLAS Model:</h3>
            <div id="atlas-results" class="calculator-results"></div>
        </div>
    </div>

    {{-- Add other model calculators here --}}
</div>