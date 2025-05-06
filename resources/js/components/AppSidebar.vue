<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
  LayoutGrid,
  MessageCircle,
  Newspaper,
  Tags,
  Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import AppLogo from './AppLogo.vue';

const { t } = useI18n();

interface Props {
  isAdmin?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  isAdmin: () => false,
});

const baseNavItems: NavItem[] = [
  {
    title: t('sidebar.dashboard'),
    href: '/dashboard',
    icon: LayoutGrid,
  },
  {
    title: t('sidebar.articles'),
    href: '/articles',
    icon: Newspaper,
  },
];

const adminNavItems: NavItem[] = [
  {
    title: t('sidebar.users'),
    href: '/users',
    icon: Users,
  },
  {
    title: t('sidebar.categories'),
    href: '/categories',
    icon: Tags,
  },
  {
    title: t('sidebar.comments'),
    href: '/comments',

    icon: MessageCircle,
  },
];

const mainNavItems = computed(() => {
  let items = [...baseNavItems];
  if (props.isAdmin) {
    items = items.concat(adminNavItems);
  }
  return items;
});
</script>

<template>
  <Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <Link :href="route('home')">
              <AppLogo />
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <NavMain :items="mainNavItems" />
    </SidebarContent>

    <SidebarFooter>
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>
