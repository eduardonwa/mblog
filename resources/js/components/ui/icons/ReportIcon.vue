<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    color?: string;
    hoverColor?: string;
    viewBox?: string;
    size?: string | number; // Tamaño del contenedor (ajustará automáticamente el SVG)
    showText?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  color: '#D3D7EA',
  hoverColor: '#F4FFC7',
  size: '24px',
  showText: false,
});

const isHovered = ref(false);
</script>

<template>
    <button class="report-icon">
        <svg
            :width="size"
            :height="size"
            viewBox="0 0 24 24"
            :fill="isHovered ? hoverColor : color"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path d="M12 14.75C11.59 14.75 11.25 14.41 11.25 14V9C11.25 8.59 11.59 8.25 12 8.25C12.41 8.25 12.75 8.59 12.75 9V14C12.75 14.41 12.41 14.75 12 14.75Z" :fill="isHovered ? hoverColor : color" />
            <path d="M12 18.0005C11.94 18.0005 11.87 17.9905 11.8 17.9805C11.74 17.9705 11.68 17.9505 11.62 17.9205C11.56 17.9005 11.5 17.8705 11.44 17.8305C11.39 17.7905 11.34 17.7505 11.29 17.7105C11.11 17.5205 11 17.2605 11 17.0005C11 16.7405 11.11 16.4805 11.29 16.2905C11.34 16.2505 11.39 16.2105 11.44 16.1705C11.5 16.1305 11.56 16.1005 11.62 16.0805C11.68 16.0505 11.74 16.0305 11.8 16.0205C11.93 15.9905 12.07 15.9905 12.19 16.0205C12.26 16.0305 12.32 16.0505 12.38 16.0805C12.44 16.1005 12.5 16.1305 12.56 16.1705C12.61 16.2105 12.66 16.2505 12.71 16.2905C12.89 16.4805 13 16.7405 13 17.0005C13 17.2605 12.89 17.5205 12.71 17.7105C12.66 17.7505 12.61 17.7905 12.56 17.8305C12.5 17.8705 12.44 17.9005 12.38 17.9205C12.32 17.9505 12.26 17.9705 12.19 17.9805C12.13 17.9905 12.06 18.0005 12 18.0005Z" :fill="isHovered ? hoverColor : color" />
            <path d="M18.06 22.1598H5.93998C3.98998 22.1598 2.49998 21.4498 1.73998 20.1698C0.989976 18.8898 1.08998 17.2398 2.03998 15.5298L8.09998 4.62984C9.09998 2.82984 10.48 1.83984 12 1.83984C13.52 1.83984 14.9 2.82984 15.9 4.62984L21.96 15.5398C22.91 17.2498 23.02 18.8898 22.26 20.1798C21.5 21.4498 20.01 22.1598 18.06 22.1598ZM12 3.33984C11.06 3.33984 10.14 4.05984 9.40998 5.35984L3.35998 16.2698C2.67998 17.4898 2.56998 18.6098 3.03998 19.4198C3.50998 20.2298 4.54998 20.6698 5.94998 20.6698H18.07C19.47 20.6698 20.5 20.2298 20.98 19.4198C21.46 18.6098 21.34 17.4998 20.66 16.2698L14.59 5.35984C13.86 4.05984 12.94 3.33984 12 3.33984Z" :fill="isHovered ? hoverColor : color" />
            <defs>
                <clipPath id="clip0_4418_7357">
                    <rect width="24" height="24" :fill="isHovered ? hoverColor : color" />
                </clipPath>
            </defs>
        </svg>
        <span class="text-icon-report" v-if="showText">Report</span>
    </button>
</template>