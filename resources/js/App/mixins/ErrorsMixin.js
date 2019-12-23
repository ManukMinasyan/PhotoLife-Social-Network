export const ErrorsMixin = {
    data() {
        return {
            errors: {}
        }
    },
    methods: {
        hasError(field) {
            if (this.errors.hasOwnProperty(field)) {
                return true;
            }

            return false;
        },
        getError(field) {
            if (this.errors.hasOwnProperty(field)) {
                return this.errors[field];
            }

            return '';
        }
    }
};