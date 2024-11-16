// текущая дата и время

export function oneYearsAgo(): Date {
    const currentDate = new Date();
    return new Date(
        currentDate.getFullYear() - 1,
        currentDate.getMonth(),
        currentDate.getDate()
    );
}
export function oneMonthAgo(): Date {
    const currentDate = new Date();
    return new Date(
        currentDate.getFullYear(),
        currentDate.getMonth() - 1,
        currentDate.getDate()
    );
}
export function threeMonthAgo(): Date {
    const currentDate = new Date();
    return new Date(
        currentDate.getFullYear(),
        currentDate.getMonth(),
        currentDate.getDate() - 90
    );
}
export function fiveYearsAgo(): Date {
    const currentDate = new Date();
    return new Date(
        currentDate.getFullYear() - 5,
        currentDate.getMonth(),
        currentDate.getDate()
    );
}
export function sevenDayAgo(): Date {
    const currentDate = new Date();
    return new Date(
        currentDate.getFullYear(),
        currentDate.getMonth(),
        currentDate.getDate() - 7
    );
}
export function yesterdayDay(): Date {
    const currentDate = new Date();
    return new Date(
        currentDate.getFullYear(),
        currentDate.getMonth(),
        currentDate.getDate() - 1
    );
}
