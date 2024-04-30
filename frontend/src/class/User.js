export default class User {

    constructor(data) {
        this.id = data?.id;
        this.username = data?.username;      
        this.email = data?.email;    
        this.owner = data?.owner;        
        this.admin = data?.is_admin;
        this.roles = data?.roles;
        this.permissions = data?.permissions;
    }

    can(action) {
        let result = false;         
        this.permissions?.forEach(element => {                                    
            if(element.name == action) {                                             
                result = true;
            }            
        });
        return result;
    }

    isOwner() {
        if(this.owner) {
            return true;
        }
        return false;
    }

    isAdmin() {
        if(this.admin) {
            return true;
        } 
        return false;
    }
        
}
