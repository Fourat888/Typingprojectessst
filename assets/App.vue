<template>
  <div>
    <h2>welcome</h2>
    <button @click="counter++"> click me {{ counter }}</button>
  </div>

</template>
<script>
export default {
data: () => ({
counter: 0
})
}
</script>