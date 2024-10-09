<div id="atlas-tab" class="tab">
    <h1>Fence Panel Calculator - ATLAS Model</h1>
    <form id="atlas-fence-calculator">
        <label for="atlas-panel-width">Panel Width (m):</label>
        <input type="number" id="atlas-panel-width" name="atlas-panel-width" step="0.01" min="0.3" max="3.3" required><br><br>

        <label for="atlas-panel-height">Panel Height (m):</label>
        <select id="atlas-panel-height" name="atlas-panel-height" required>
        </select><br><br>

        <label for="atlas-number-of-panels">Number of Panels:</label>
        <input type="number" id="atlas-number-of-panels" name="atlas-number-of-panels" min="1" required><br><br>

        <button type="button" id="atlas-calculate">Calculate</button>
    </form>

    <h2>Required Materials for ATLAS Model:</h2>
    <div id="atlas-results"></div>
</div>