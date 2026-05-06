<script setup lang="ts">
import type { DropdownMenuItem, NavigationMenuItem } from "@nuxt/ui";
import { Roles } from "~/resources/role";

const open = defineModel<boolean>("open", {
  default: false,
});

const { user } = useUserSession();
const { hasAnyRole } = useUserAccess();
const profileStore = useProfileStore();
const colorMode = useColorMode();

const logout = async () => {
  await profileStore.logout();
  navigateTo("/login");
};

function getItems(state: "collapsed" | "expanded") {
  const items: NavigationMenuItem[] = [
    {
      label: "Dashboard",
      icon: "i-lucide-layout-dashboard",
      to: "/",
    },
    {
      label: "Posts",
      icon: "i-lucide-newspaper",
      defaultOpen: true,
      children:
        state === "expanded"
          ? [
              {
                label: "All posts",
                icon: "i-lucide-list",
                to: "/posts",
              },
              {
                label: "Create post",
                icon: "i-lucide-circle-plus",
                to: "/posts/create",
              },
            ]
          : [],
    },
  ];

  if (hasAnyRole([Roles.Admin, Roles.SuperAdmin])) {
    items.push({
      label: "Users",
      icon: "i-lucide-users",
      defaultOpen: true,
      children:
        state === "expanded"
          ? [
              {
                label: "All users",
                icon: "i-lucide-users-round",
                to: "/users",
              },
              {
                label: "Create user",
                icon: "i-lucide-user-plus",
                to: "/users/create",
              },
            ]
          : [],
    });
  }

  return items;
}

const userItems = computed<DropdownMenuItem[][]>(() => [
  [
    {
      label: "Profile",
      icon: "i-lucide-user",
      to: "/profile",
    },
  ],
  [
    {
      label: "Appearance",
      icon: "i-lucide-sun-moon",
      children: [
        {
          label: "Light",
          icon: "i-lucide-sun",
          type: "checkbox",
          checked: colorMode.value === "light",
          onUpdateChecked(checked: boolean) {
            if (checked) {
              colorMode.preference = "light";
            }
          },
          onSelect(e: Event) {
            e.preventDefault();
          },
        },
        {
          label: "Dark",
          icon: "i-lucide-moon",
          type: "checkbox",
          checked: colorMode.value === "dark",
          onUpdateChecked(checked: boolean) {
            if (checked) {
              colorMode.preference = "dark";
            }
          },
          onSelect(e: Event) {
            e.preventDefault();
          },
        },
      ],
    },
  ],
  [
    {
      label: "GitHub",
      icon: "i-simple-icons-github",
      to: "https://github.com/shisuiufa",
      target: "_blank",
    },
    {
      label: "Log out",
      icon: "i-lucide-log-out",
      onSelect: logout,
    },
  ],
]);
</script>

<template>
  <USidebar
    v-model:open="open"
    collapsible="icon"
    rail
    :ui="{
      container: 'h-full',
      inner: 'bg-elevated/25 divide-transparent',
      body: 'py-0',
    }"
  >
    <template #header="{ state }">
      <NuxtLink to="/" class="flex min-w-0 items-center gap-3 py-1.5">
        <div
          class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 ring-1 ring-primary/20"
        >
          <UIcon name="i-lucide-newspaper" class="size-5 text-primary" />
        </div>

        <div v-if="state === 'expanded'" class="min-w-0">
          <p class="truncate font-semibold leading-none">Brief Journal</p>
        </div>
      </NuxtLink>
    </template>

    <template #default="{ state }">
      <UNavigationMenu
        :key="state"
        :items="getItems(state)"
        orientation="vertical"
        :ui="{ link: 'p-1.5 overflow-hidden' }"
      />
    </template>

    <template #footer>
      <UDropdownMenu
        :items="userItems"
        :content="{ align: 'center', collisionPadding: 12 }"
        :ui="{ content: 'w-(--reka-dropdown-menu-trigger-width) min-w-48' }"
      >
        <UButton
          v-bind="user"
          :label="user?.email"
          trailing-icon="i-lucide-chevrons-up-down"
          color="neutral"
          variant="ghost"
          square
          class="w-full data-[state=open]:bg-elevated overflow-hidden"
          :ui="{
            trailingIcon: 'text-dimmed ms-auto',
          }"
        />
      </UDropdownMenu>
    </template>
  </USidebar>
</template>
