<template>
  <section class="my-12 border-b border-t py-12 md:py-16">
    <div class="container mx-auto px-4">
      <div class="flex flex-col items-center gap-10 md:flex-row">
        <div class="md:w-1/2">
          <h2 class="mb-6 text-3xl font-bold">About Vue Blog</h2>
          <p class="mb-4 text-lg text-muted-foreground">
            We're a team of passionate web developers and designers
            sharing our knowledge and experience with the community.
          </p>
          <p class="mb-6 text-lg text-muted-foreground">
            Our mission is to provide high-quality, practical content
            that helps developers build better web applications and
            advance their careers.
          </p>
          <div class="flex gap-4">
            <button
              class="rounded-md bg-secondary px-4 py-2 text-secondary-foreground hover:bg-secondary/80"
            >
            Contact Us
            </button>
          </div>
        </div>
        <div class="flex justify-center md:w-1/2">
          <div class="grid grid-cols-2 gap-4">
            <div
              class="flex flex-col items-center rounded-lg bg-muted p-6"
            >
              <span class="mb-2 text-3xl font-bold text-primary"
                >50+</span
              >
              <span class="text-sm text-muted-foreground"
                >Articles Published</span
              >
            </div>
            <div
              class="flex flex-col items-center rounded-lg bg-muted p-6"
            >
              <span class="mb-2 text-3xl font-bold text-primary"
                >10k+</span
              >
              <span class="text-sm text-muted-foreground"
                >Monthly Readers</span
              >
            </div>
            <div
              class="flex flex-col items-center rounded-lg bg-muted p-6"
            >
              <span class="mb-2 text-3xl font-bold text-primary"
                >15+</span
              >
              <span class="text-sm text-muted-foreground"
                >Expert Authors</span
              >
            </div>
            <div
              class="flex flex-col items-center rounded-lg bg-muted p-6"
            >
              <span class="mb-2 text-3xl font-bold text-primary"
                >24/7</span
              >
              <span class="text-sm text-muted-foreground"
                >Support Available</span
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
