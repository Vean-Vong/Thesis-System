import store from '../store';
export default class User {

    constructor(data) {
        this.id = data?.id;
        this.name = data?.name;
        this.email = data?.email;        
        this.permissions = data?.permissions;
        this.roles = data?.roles;
    }

    can(action) {
        let verify = false;
        store.state.auth?.user?.permissions?.forEach(element => {
            if(element.includes(action) === true) {
                verify = true;
            }
        });
        return verify;
    }

}
