export default class User {

    constructor(data) {
        this.id = data.user.id;
        this.name = data.user.name;      
        this.email = data.user.email;    
        this.owner = data.user.owner;        
        this.admin = data.user.is_admin;
        this.organization = data.user.organization
        this.roles = data.user.roles;
        this.permissions = data.user.permissions;
        this.is_school = data.user.is_school;
    }

    can(action) {
        let result = false;         
        this.permissions.forEach(element => {                                    
            if(element.name.includes(action) === true) {                                             
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
