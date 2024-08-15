<template>
    <div>
        <header-site></header-site>
        <router-view></router-view>
    </div>
</template>

<script>
import SiteHeaderComponent from "./SiteHeaderComponent.vue";

export default {
    components: {
        headerSite: SiteHeaderComponent
    }
}
</script>
