import * as models from "./modules";
export class Api {
    public static User() {
        return models.ApiUser.getInstance();
    }
    public static Leads() {
        return models.ApiLeads.getInstance();
    }
}
