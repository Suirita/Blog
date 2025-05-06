<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <section class="bg-muted/50 py-16 md:py-24">
    <div class="container mx-auto px-4 text-center">
      <h1
        class="mb-4 text-4xl font-bold tracking-tight md:text-5xl lg:text-6xl"
      >
        Discover Insightful Articles
      </h1>
      <p
        class="mx-auto mb-8 max-w-2xl text-lg text-muted-foreground md:text-xl"
      >
        Explore a wide range of topics written by experts and
        enthusiasts. Stay informed with the latest trends and
        insights.
      </p>
      <div
        class="flex items-center justify-center gap-2 md:flex-row md:gap-4"
      >
        <Button>
          <Link href="/login" class="px-8">
            Publish your own Articles
          </Link>
        </Button>
        <Button variant="outline">
          <Link href="/articles" class="px-8"> Browse Articles </Link>
        </Button>
      </div>
    </div>
  </section>
</template>
