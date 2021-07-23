<template>
    <div class="continer">
        <div class="form-group">
            <input
                class="form-control"
                type="text"
                @blur="saveName"
                v-model="name"
            />
        </div>
        <div class="alert alert-danger" role="alert" v-if="errored">
            Data request error.
        </div>
        <div class="text-center">
            <div class="spinner-border" role="status" v-if="loading">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['deskId'],
    data() {
        return {
            name: null,
            errorred: false,
            loading: true,
        };
    },
    methods: {
        saveName() {
            axios
                .post('/api/v1/desks/' + this.deskId, {
                    _method: 'PUT',
                    name: this.name,
                })
                .then(response => {
                    // this.name = response.data.data.name;
                })
                .catch(error => {
                    console.log(error);
                    this.errorred = true;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
    mounted() {
        axios
            .get('/api/v1/desks/' + this.deskId)
            .then(response => {
                this.name = response.data.data.name;
            })
            .catch(error => {
                console.log(error);
                this.errorred = true;
            })
            .finally(() => {
                this.loading = false;
            });
    },
};
</script>
