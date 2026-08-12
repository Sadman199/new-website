<template id="cms-section-card-template">
    <div class="cms-section-card" data-index="">
        <div class="cms-section-card__header">
            <div class="cms-section-card__left">
                <span class="cms-section-handle" title="Drag to reorder"><i class="fas fa-grip-vertical"></i></span>
                <span class="cms-section-icon"><i class="fas fa-cube"></i></span>
                <div class="cms-section-meta">
                    <span class="cms-section-label"></span>
                    <span class="cms-section-desc"></span>
                </div>
            </div>
            <div class="cms-section-card__actions">
                <button type="button" class="btn btn-sm btn-light cms-section-toggle" title="Expand/collapse"><i class="fas fa-chevron-down"></i></button>
                <button type="button" class="btn btn-sm btn-danger cms-section-remove" title="Remove section"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="cms-section-body"></div>
    </div>
</template>
