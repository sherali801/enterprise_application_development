using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using PMS.Entities;
using System.Data.SqlClient;

namespace PMS.DAL
{
	public static class ProductDAO
	{
		public static int Save(ProductDTO dto)
		{
			using (DBHelper helper = new DBHelper())
			{
				String sqlQuery = "";
				if (dto.ProductID > 0)
				{
					sqlQuery = String.Format("Update dbo.Products Set Name='{0}',Price='{1}',PictureName='{2}',ModifiedOn='{3}',ModifiedBy='{4}' Where ProductID={5}",
						dto.Name, dto.Price, dto.PictureName, dto.ModifiedOn, dto.ModifiedBy, dto.ProductID);
					helper.ExecuteQuery(sqlQuery);
					return dto.ProductID;
				}
				else
				{
					sqlQuery = String.Format("INSERT INTO dbo.Products(Name, Price, PictureName, CreatedOn, CreatedBy,IsActive) VALUES('{0}','{1}','{2}','{3}','{4}',{5}); Select @@IDENTITY",
						dto.Name, dto.Price, dto.PictureName, dto.CreatedOn, dto.CreatedBy, 1);

					var obj = helper.ExecuteScalar(sqlQuery);
					return Convert.ToInt32(obj);
				}
			}
		}
		public static ProductDTO GetProductById(int pid)
		{
			var query = String.Format(@"SELECT ProductID, p.Name AS pName, price, p.PictureName AS pPictureName, p.CreatedOn AS pCreatedOn, p.CreatedBy AS pCreatedBy, p.ModifiedOn AS pModifiedOn, p.ModifiedBy AS pModifiedBy, p.IsActive AS pIsActive, u.Name AS uName 
										from dbo.Products p, dbo.Users u 
										Where p.CreatedBy = u.UserID 
										AND ProductId={0}", pid);

			using (DBHelper helper = new DBHelper())
			{
				var reader = helper.ExecuteReader(query);

				ProductDTO dto = null;

				if (reader.Read())
				{
					dto = FillDTO(reader);
				}

				return dto;
			}
		}

		public static List<ProductDTO> GetAllProducts(Boolean pLoadComments=false)
		{
			var query = @"Select ProductID, p.Name AS pName, price, p.PictureName AS pPictureName, p.CreatedOn AS pCreatedOn, p.CreatedBy AS pCreatedBy, p.ModifiedOn AS pModifiedOn, p.ModifiedBy AS pModifiedBy, p.IsActive AS pIsActive, u.Name as uName 
						  from dbo.Products p, dbo.Users u 
						  Where p.CreatedBy = u.UserID 
						  AND p.IsActive = 1";

			using (DBHelper helper = new DBHelper())
			{
				var reader = helper.ExecuteReader(query);
				List<ProductDTO> list = new List<ProductDTO>();

				while (reader.Read())
				{
					var dto = FillDTO(reader);
					if (dto != null)
					{
						list.Add(dto);
					}
				}
				if (pLoadComments == true)
				{
					//var commentsList = CommentDAO.GetAllComments();

					var commentsList = CommentDAO.GetTopComments(2);

					foreach (var prod in list)
					{
						List<CommentDTO> prodComments = commentsList.Where(c => c.ProductID == prod.ProductID).ToList();
						prod.Comments = prodComments;
					}
				}
				return list;
			}
		}

		public static int DeleteProduct(int pid)
		{
			String sqlQuery = String.Format("Update dbo.Products Set IsActive=0 Where ProductID={0}", pid);

			using (DBHelper helper = new DBHelper())
			{
				return helper.ExecuteQuery(sqlQuery);
			}
		}

		private static ProductDTO FillDTO(SqlDataReader reader)
		{
			var dto = new ProductDTO();
			dto.ProductID = reader.GetInt32(reader.GetOrdinal("ProductID"));
			dto.Name = reader.GetString(reader.GetOrdinal("pName"));
			dto.Price = reader.GetDouble(reader.GetOrdinal("Price"));
			dto.PictureName = reader.GetString(reader.GetOrdinal("pPictureName"));
			dto.CreatedOn = reader.GetDateTime(reader.GetOrdinal("pCreatedOn"));
			dto.CreatedBy = reader.GetInt32(reader.GetOrdinal("pCreatedBy"));
			if (reader.GetValue(reader.GetOrdinal("pModifiedOn")) != DBNull.Value)
				dto.ModifiedOn = reader.GetDateTime(reader.GetOrdinal("pModifiedOn"));
			if (reader.GetValue(reader.GetOrdinal("pModifiedBy")) != DBNull.Value)
				dto.ModifiedBy = reader.GetInt32(reader.GetOrdinal("pModifiedBy"));

			dto.IsActive = reader.GetBoolean(reader.GetOrdinal("pIsActive"));
			dto.NameofCreator = reader.GetString(reader.GetOrdinal("uName"));
			return dto;
		}
	}
}
