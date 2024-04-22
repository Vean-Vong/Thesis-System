<template>
  <div>
    <div class="bg-image">
      <div class="row flex justify-center items-center login-form-center">
        <div class="col-12 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="bg-card-login shadow-3 q-pa-sm">
            <div class="row flex justify-center items-center">
              <div
                class="col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 border-edit"
              >
                <div class="flex justify-center items-center q-py-xl">
                  <img src="../../../public/schoollogo.jpg" alt="" class="img-logo" />
                </div>
              </div>
              <div class="col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="q-mx-lg">
                  <form @submit.prevent="login">
                    <div class="login q-my-sm">Account</div>
                    <q-input
                      type="email"
                      v-model="form.email"
                      outlined
                      dense
                      class="q-mb-md"
                      label="Email"
                    ></q-input>
                    <q-input
                      type="password"
                      v-model="form.password"
                      outlined
                      dense
                      class="q-mt-md"
                      label="Password"
                    ></q-input>
                    <div class="border-btn">
                      <button type="submit" class="q-my-md btn-btn">Sign in</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    form: {
      email: null,
      password: null,
    },
  }),
  mounted() {},
  methods: {
    login() {
      this.$store
        .dispatch("auth/login", this.form)
        .then((response) => {
          let redirect_location = window.localStorage.getItem("redirect");
          if (redirect_location) {
            this.$router.push(redirect_location);
            window.localStorage.removeItem("redirect");
          } else {
            this.$router.push({ name: "home" });
          }
        })
        .catch(({ response }) => {
          if (response) {
            this.error =
              response.data.error_description || "Cannot connect to the remote server.";
          } else {
            this.error = "Cannot connect to the remote server.";
          }
        });
    },
  },
};
</script>

<style scoped>
.bg-card-login {
  border-radius: 10px;
  background-color: rgb(255, 255, 255);
  /* box-shadow: 0px 0px 5px #4338ca; */
}

.bg-image {
  background-image: url("../../../public/bglogin.png");
  background-position: center;
  background-size: cover;
  object-fit: cover;
  height: 100vh;
}

.login-form-center {
  position: absolute;
  width: 100%;
  height: 100%;
}

.border-edit {
  /* background-color: #475569; */
  border-radius: 10px;
  background: linear-gradient(60deg, #4f46e5, #fca5a5);
}

.img-logo {
  border-radius: 50%;
  width: 200px;
  border: 5px solid white;
}

.btn-btn {
  display: flex;
  justify-content: center;
  align-items: end;
  color: white;
  width: 100%;
  border: none;
  outline: none;
  background-color: #6366f1;
  border-radius: 6px;
  padding: 8px 0px;
  cursor: pointer;
  transition: ease;
  transition-duration: 100ms;
}

.btn-btn:active {
  background-color: #4338ca;
}

.login {
  font-family: Verdana, Geneva, Tahoma, sans-serif;
  font-size: x-large;
  font-weight: bold;
  display: flex;
  justify-content: center;
  color: #4338ca;
}

.border-btn {
  display: flex;
  justify-content: center;
}
</style>
