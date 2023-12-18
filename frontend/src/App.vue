<template>
  <NavbarComponent />
  <LoadingComponent />
  <router-view />
</template>
<script>
import LoadingComponent from '@/components/LoadingComponent';
import NavbarComponent from '@/components/NavbarComponent';

export default {
  components: {
    NavbarComponent,
    LoadingComponent,
  },
}

</script>
