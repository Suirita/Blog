<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/components/ui/toast';
import { ref } from 'vue';

const { toast } = useToast();

const form = ref({
  name: '',
  email: '',
  message: '',
});

const isSubmitting = ref(false);

const submitForm = async () => {
  isSubmitting.value = true;

  try {
    // Here you would typically make an API call to your Laravel backend
    // For example: await axios.post('/api/contact', form.value);

    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1000));

    toast({
      title: 'Message sent!',
      description: 'We will get back to you as soon as possible.',
      variant: 'default',
    });

    // Reset form
    form.value = {
      name: '',
      email: '',
      message: '',
    };
  } catch (error) {
    toast({
      title: 'Error',
      description:
        'There was a problem sending your message. Please try again.',
      variant: 'destructive',
    });
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <section id="contact" className="p-16 bg-secondary/50">
    <div className="container">
      <div className="mb-10 text-center">
        <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
          Contact Us
        </h2>
        <p className="mt-4 text-muted-foreground">
          Have questions or need assistance? We're here to help!
        </p>
      </div>
      <div className="grid gap-10 md:grid-cols-2">
        <div className="space-y-6">
          <div>
            <h3 className="text-xl font-semibold">Get in Touch</h3>
            <p className="mt-2 text-muted-foreground">
              Our customer support team is available 24/7 to assist
              you with any inquiries or issues you may have.
            </p>
          </div>
          <div className="space-y-4">
            <div>
              <h4 className="font-medium">Email</h4>
              <a
                href="mailto:support@stayease.com"
                className="text-primary hover:underline"
              >
                support@stayease.com
              </a>
            </div>
            <div>
              <h4 className="font-medium">Phone</h4>
              <a
                href="tel:+11234567890"
                className="text-primary hover:underline"
              >
                +1 (123) 456-7890
              </a>
            </div>
          </div>
        </div>
        <div>
          <form onSubmit="{handleSubmit}" className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="name">Name</Label>
              <Input
                id="name"
                name="name"
                value="{formData.name}"
                onChange="{handleChange}"
                required
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="email">Email</Label>
              <Input
                id="email"
                name="email"
                type="email"
                value="{formData.email}"
                onChange="{handleChange}"
                required
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="subject">Subject</Label>
              <Input
                id="subject"
                name="subject"
                value="{formData.subject}"
                onChange="{handleChange}"
                required
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="message">Message</Label>
              <Textarea
                id="message"
                name="message"
                rows="{5}"
                value="{formData.message}"
                onChange="{handleChange}"
                required
              />
            </div>
            <Button
              className="w-full bg-primary text-white hover:bg-primary/80 rounded-lg px-4 py-2"
            >
              Send
            </Button>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>
