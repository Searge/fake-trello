<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container" id="navbarNav">
                <div class="navbar-nav">
                    <router-link
                        class="nav-item nav-link"
                        :to="{ name: 'home' }"
                    >
                        Home
                    </router-link>
                    <router-link
                        class="nav-item nav-link"
                        :to="{ name: 'desks' }"
                    >
                        Desks
                    </router-link>
                </div>
            </div>
            <div></div>
        </nav>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    setup() {
        name: 'App';
    },
};
</script>
