<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link, router } from '@inertiajs/vue3';

interface Props {
  isAuthenticated: boolean;
}

defineProps<Props>();

const logout = () => {
  router.post('logout');
};
</script>

<template>
  <header class="border-b border-border">
    <div
      class="container mx-auto flex items-center justify-between px-4 py-4"
    >
      <div class="flex items-center">
        <a href="/articles" class="text-xl font-bold text-primary"
          >BlogHub</a
        >
      </div>
      <nav class="flex items-center gap-4">
        <div v-if="isAuthenticated">
          <button
            class="px-4 py-2 text-sm font-medium text-foreground transition-colors hover:text-primary"
            @click="logout"
          >
            Logout
          </button>
        </div>
        <div v-else>
          <Link
            href="/login"
            class="px-4 py-2 text-sm font-medium text-foreground transition-colors hover:text-primary"
          >
            Login
          </Link>
          <Button as-child variant="default">
            <Link href="/register"> Register </Link>
          </Button>
        </div>
      </nav>
    </div>
  </header>
</template>
