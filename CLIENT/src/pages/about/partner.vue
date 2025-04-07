<template>
  <div class="client-title">
    <img
      src="https://gangsan.com.kh/wp-content/uploads/2018/06/BANNER.png"
      alt="Banner"
    >
  </div>

  <div class="our-client">
    <img
      v-for="(image, index) in clientLogos"
      :key="index"
      :src="image"
      class="client-logo"
      loading="lazy"
      alt="Client Logo"
    >
  </div>
</template>

<script>
export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      clientLogos: [
        'https://gangsan.com.kh/wp-content/uploads/2018/06/1398045-4.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/1408663.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/11667447_774277732688808_6189435679244768491_n.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/1442997523-1.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/1504238749-78-ufo-store-siem-reap.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/Aeonmall_corporation_logo.svg_-768x768.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/Borey_New_World_logo-1.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/download.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/gnc1.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/logo_prudential_notagline.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/Manulife1.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/Ming_Wuoy_Group_CoLtd.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/small-144530669584298.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/sunfix-1.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/1371560.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/Crow_logo.gif',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/download-1.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/AIS-Logo-768x361.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/aii-logo.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/download-2.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/Kinderland_Logo_h.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/LOG42201335015PMbb99c6a46650.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/Borey_New_World_logo-1.jpg',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/logo.png',
        'https://gangsan.com.kh/wp-content/uploads/2018/06/western-international-school-16740-c-1.png',
      ],
    }
  },
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
}

.client-title {
  margin: -20px;
  padding-bottom: 20px;
}

.client-title img {
  width: 100%;
  height: 300px;
  object-fit: cover;
}

.our-client {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 15px;
  padding: 20px;
}

.client-logo {
  width: 150px;
  padding: 5px;
}
</style>

<route lang="yaml">
meta:
  title: Partner
  layout: default
  subject: Auth
  active: 'partner'
</route>
