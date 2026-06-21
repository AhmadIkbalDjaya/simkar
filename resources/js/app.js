document.addEventListener("alpine:init", () => {
    Alpine.data("searchableSelect", () => ({
        id: "",
        model: "",
        options: [],
        selectedValue: "",
        emptyValue: null,
        query: "",
        open: false,
        activeIndex: -1,

        setup() {
            this.id = this.$root.dataset.id;
            this.model = this.$root.dataset.model;
            this.options = JSON.parse(this.$root.dataset.options);
            this.emptyValue = JSON.parse(this.$root.dataset.emptyValue);
            this.selectedValue = this.$root.dataset.selected
                ? String(this.$root.dataset.selected)
                : "";
            this.restoreSelectedLabel();
        },

        get filteredOptions() {
            const term = this.query.trim().toLocaleLowerCase("id");
            if (!term) return this.options;

            return this.options.filter((option) =>
                option.search.toLocaleLowerCase("id").includes(term),
            );
        },

        get activeOptionId() {
            const option = this.filteredOptions[this.activeIndex];
            return this.open && option ? this.optionId(option) : null;
        },

        optionId(option) {
            const value = option.value.replace(/[^a-zA-Z0-9_-]/g, "-");
            return `${this.id}-option-${value}`;
        },

        firstEnabledIndex() {
            return this.filteredOptions.findIndex((option) => !option.disabled);
        },

        filter() {
            this.open = true;
            this.activeIndex = this.firstEnabledIndex();
        },

        openDropdown() {
            this.open = true;
            this.query = "";
            this.activeIndex = this.options.findIndex(
                (option) => option.value === this.selectedValue,
            );
            if (this.activeIndex < 0) {
                this.activeIndex = this.firstEnabledIndex();
            }
        },

        toggle() {
            if (this.open) {
                this.close();
                return;
            }

            this.$refs.searchInput.focus();
        },

        close() {
            this.open = false;
            this.activeIndex = -1;
            this.restoreSelectedLabel();
        },

        move(direction) {
            if (!this.open) {
                this.openDropdown();
                if (direction < 0 && this.firstEnabledIndex() >= 0) {
                    this.activeIndex = this.filteredOptions.length;
                    this.move(direction);
                }
                return;
            }

            const count = this.filteredOptions.length;
            if (!count) return;

            for (let offset = 1; offset <= count; offset++) {
                const index =
                    (this.activeIndex + direction * offset + count) % count;
                if (!this.filteredOptions[index].disabled) {
                    this.activeIndex = index;
                    break;
                }
            }
            this.$nextTick(() => {
                document
                    .getElementById(this.activeOptionId)
                    ?.scrollIntoView({ block: "nearest" });
            });
        },

        selectActive() {
            const option = this.filteredOptions[this.activeIndex];
            if (option) this.select(option);
        },

        select(option) {
            if (option.disabled) return;

            this.selectedValue = option.value;
            this.query = option.label;
            this.open = false;
            this.activeIndex = -1;
            this.$wire.set(this.model, option.value);
        },

        clearSelection() {
            this.selectedValue = "";
            this.query = "";
            this.open = true;
            this.activeIndex = this.firstEnabledIndex();
            this.$wire.set(this.model, this.emptyValue);
            this.$refs.searchInput.focus();
        },

        restoreSelectedLabel() {
            const selected = this.options.find(
                (option) => option.value === this.selectedValue,
            );
            this.query = selected?.label ?? "";
        },
    }));
});
