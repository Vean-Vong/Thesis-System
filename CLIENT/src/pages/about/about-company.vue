<template>
  <div class="overlay">
    <div class="logo">
      <img
        src="https://d1yc6vwxvprgjf.cloudfront.net/kh/logo_images/large/1385448760/1521454?1385448760"
        alt="Company Logo"
      >
    </div>

    <div class="about-company">
      <div class="about-content">
        <p>{{ $t('aboutDescription') }}</p>
      </div>
      <div class="about-image">
        <img
          src="https://lh3.googleusercontent.com/p/AF1QipMPq2TdJNhvkJ_kTM7BArNsHN320juC_CmYLLuz=w408-h544-k-no"
          alt="Company Image"
        >
        <p>
          <strong>Address:</strong> #9, Twin Road at Mohatep Park, Sangkat Svay Por, Battambang Province
        </p>
      </div>
    </div>
  </div>
</template>

<style>
/* General styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.logo {
  display: flex;
  padding-left: 200px;
  margin-top: -50px;
}

.logo img {
  max-width: 250px;
  height: auto;
}

/* About company section */
.about-company {
  display: flex;
  gap: 30px;
}

/* About text */
.about-content {
  max-width: 600px;
  font-size: 16px;
  text-align: justify;
}

/* About image */
.about-image {
  max-width: 540px;
  margin-top: -150px;
}

.about-image img {
  width: 100%;
  height: auto;
  object-fit: cover;
  border-radius: 5px;
}

.about-image p {
  font-size: 14px;
  margin-top: 10px;
}
</style>

<route lang="yaml">
meta:
  title: About Company
  layout: default
  subject: Auth
  active: 'about-company'
</route>
