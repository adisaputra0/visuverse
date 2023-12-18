<template>
  <LoadingComponent />
  <router-view />
</template>
<script>
import LoadingComponent from '@/components/LoadingComponent';

export default {
  components: {
    LoadingComponent,
  },
}

</script>
