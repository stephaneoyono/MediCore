export const formatCurrency = (value) => {
    if (value === null || value === undefined || value === "") {
        return "0 FCFA";
    }

    const numericValue =
        typeof value === "number"
            ? value
            : Number(String(value).replace(/[^\d.,-]/g, "").replace(",", "."));

    if (Number.isNaN(numericValue)) {
        return String(value).includes("FCFA") ? String(value) : `${value} FCFA`;
    }

    return `${new Intl.NumberFormat("fr-FR", {
        maximumFractionDigits: 0,
    }).format(numericValue)} FCFA`;
};
