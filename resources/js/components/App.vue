<template>
    <div class="container">
        <p>
            <router-link :to="{ name: 'home' }">Home</router-link>
        </p>
        <div>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    setup() {
        name: 'App';
    },
};
</script>
