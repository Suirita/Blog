<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Facebook, Instagram, Twitter } from 'lucide-vue-next';
</script>

<template>
  <footer class="border-t border-border bg-muted py-8">
    <div class="container mx-auto px-4">
      <div
        class="flex flex-col items-center justify-between md:flex-row"
      >
        <div class="mb-4 md:mb-0">
          <p class="text-sm text-muted-foreground">
            &copy; {{ new Date().getFullYear() }} BlogHub. All rights
            reserved.
          </p>
        </div>
        <div class="flex flex-col gap-4 md:flex-row md:gap-8">
          <Link
            href="/privacy-policy"
            class="text-sm text-muted-foreground transition-colors hover:text-primary"
          >
            Privacy Policy
          </Link>
          <Link
            href="/terms-of-service"
            class="text-sm text-muted-foreground transition-colors hover:text-primary"
          >
            Terms of Service
          </Link>
        </div>
        <div class="mt-4 flex gap-4 md:mt-0">
          <a
            href="#"
            class="text-muted-foreground transition-colors hover:text-primary"
          >
            <Facebook class="h-5 w-5" />
            <span class="sr-only">Facebook</span>
          </a>
          <a
            href="#"
            class="text-muted-foreground transition-colors hover:text-primary"
          >
            <Twitter class="h-5 w-5" />
            <span class="sr-only">Twitter</span>
          </a>
          <a
            href="#"
            class="text-muted-foreground transition-colors hover:text-primary"
          >
            <Instagram class="h-5 w-5" />
            <span class="sr-only">Instagram</span>
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>
