<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRightIcon } from "@vue-hero-icons/outline"

</script>

<template>
  <nav aria-label="Navigation" className="my-10 w-full ">

    <ul className="flex h-8 min-w-full items-center justify-center gap-4 text-sm">
      <li v-for="page in pagination.links" :key="page.url">

        <a :href="page.url ? page.url : '#'" :class="{
          'pagination-link flex h-8 items-center justify-center px-4 leading-tight': true,
          'bg-black text-white': page.active
        }">


          <template v-if="page.label == 'Next &raquo;'">
            <ArrowRightIcon size="1.5x" class="h-5 w-5" />
          </template>

          <template v-else-if="page.label === '&laquo; Previous' || page.label === '&laquo; Trang sau'">
            <ArrowRightIcon class="h-5 w-5 text-primary-500 hover:bg-primary-100" />
          </template>

          <template v-else>
            {{ page.label }}
          </template>

        </a>


      </li>

    </ul>
  </nav>
</template>
<script>
export default {
  props: ['pagination'],
  setup(props) {
    const isActive = (page) => page === props.pagination.currentPage;

    const getPaginationClasses = (page) => ({
      'active': isActive(page),
      'hover:bg-primary-100': !isActive(page),
    });

    return {
      isActive,
      getPaginationClasses,
    };
  },
};
</script>
