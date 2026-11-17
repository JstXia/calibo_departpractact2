<div class="form-grid">
    <div class="field">
        <label for="equipment_name">Equipment name <span class="required-mark">*</span></label>
        <input id="equipment_name" name="equipment_name" type="text" value="{{ old('equipment_name', $equipment->equipment_name) }}" placeholder="e.g. Carbon fiber paddle" maxlength="255" autocomplete="off" required aria-invalid="@error('equipment_name') true @else false @enderror" @error('equipment_name') aria-describedby="equipment_name-error" @enderror>
        @error('equipment_name')<div class="error" id="equipment_name-error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="category">Category <span class="required-mark">*</span></label>
        <select id="category" name="category" required aria-invalid="@error('category') true @else false @enderror" @error('category') aria-describedby="category-error" @enderror>
            <option value="">Choose a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $equipment->category) === $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('category')<div class="error" id="category-error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="unit_price">Unit price <span class="required-mark">*</span></label>
        <div class="input-prefix-wrap">
            <span class="input-prefix" aria-hidden="true">$</span>
            <input id="unit_price" type="number" min="0" step="0.01" name="unit_price" value="{{ old('unit_price', $equipment->unit_price) }}" placeholder="0.00" inputmode="decimal" required aria-invalid="@error('unit_price') true @else false @enderror" @error('unit_price') aria-describedby="unit_price-error" @enderror>
        </div>
        @error('unit_price')<div class="error" id="unit_price-error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="availability_status">Availability <span class="required-mark">*</span></label>
        <select id="availability_status" name="availability_status" required aria-invalid="@error('availability_status') true @else false @enderror" @error('availability_status') aria-describedby="availability_status-error" @enderror>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('availability_status', $equipment->availability_status ?: 'available') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @error('availability_status')<div class="error" id="availability_status-error">{{ $message }}</div>@enderror
    </div>

    <div class="field full">
        <label for="description">Description <span class="required-mark">*</span></label>
        <textarea id="description" name="description" rows="5" placeholder="Add useful details such as materials, size, or included accessories." required aria-invalid="@error('description') true @else false @enderror" @error('description') aria-describedby="description-error" @enderror>{{ old('description', $equipment->description) }}</textarea>
        <div class="field-hint">A short description helps identify this item in your inventory.</div>
        @error('description')<div class="error" id="description-error">{{ $message }}</div>@enderror
    </div>
</div>
