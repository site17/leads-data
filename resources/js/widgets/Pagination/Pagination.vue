<template>
    <div>
      <div v-for="item in paginatedData" :key="item.id">
        <!-- Отображение данных -->
        <p>{{ item.name }}</p>
      </div>
  
      <div class="pagination">
        <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue'
  
  export default {
    props: {
      data: {
        type: Array,
        required: true
      },
      itemsPerPage: {
        type: Number,
        default: 10
      }
    },
    setup(props) {
      const currentPage = ref(1)
  
      // Подсчёт общего количества страниц
      const totalPages = computed(() => Math.ceil(props.data.length / props.itemsPerPage))
  
      // Получение данных для текущей страницы
      const paginatedData = computed(() => {
        const start = (currentPage.value - 1) * props.itemsPerPage
        const end = start + props.itemsPerPage
        return props.data.slice(start, end)
      })
  
      // Переключение на предыдущую страницу
      const prevPage = () => {
        if (currentPage.value > 1) {
          currentPage.value--
        }
      }
  
      // Переключение на следующую страницу
      const nextPage = () => {
        if (currentPage.value < totalPages.value) {
          currentPage.value++
        }
      }
  
      return {
        currentPage,
        totalPages,
        paginatedData,
        prevPage,
        nextPage
      }
    }
  }
  </script>
  
  <style>
  .pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
  }
  </style>
  