import 'package:to_do_list_app/models/auth_response.dart';
import 'package:to_do_list_app/models/team.dart';
import 'package:to_do_list_app/services/auth_service.dart';

class TeamMemberRepository {
  final AuthService authService;
  TeamMemberRepository(this.authService);
  String path = '/api/v1/member';

  Future<List<TeamMember>> getMembersByUserId(int user_id) async {
    final response = await authService.get('$path/$user_id');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      List<dynamic> data = response.data['data'];
      return data.map((team) => TeamMember.fromJson(team)).toList();
    } else {
      throw Exception('Lỗi khi tải thành viên nhóm');
    }
  }

  Future<List<User>> getMembersByTeamId(int team_id) async {
    final response = await authService.get('$path/by-team/$team_id');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      List<dynamic> data = response.data['data'];
      // API returns TeamMemberDTO with user object inside
      return data.map((member) {
        // member is TeamMemberDTO, extract user from it
        if (member['user'] != null) {
          final userData = Map<String, dynamic>.from(member['user']);
          // Laravel returns 'full_name' but Flutter expects 'name'
          if (userData.containsKey('full_name') && !userData.containsKey('name')) {
            userData['name'] = userData['full_name'];
          }
          return User.fromJson(userData);
        } else {
          // Fallback: if user is not nested, try parsing member directly
          final memberData = Map<String, dynamic>.from(member);
          if (memberData.containsKey('full_name') && !memberData.containsKey('name')) {
            memberData['name'] = memberData['full_name'];
          }
          return User.fromJson(memberData);
        }
      }).toList();
    } else {
      throw Exception('Lỗi khi tải danh sách thành viên nhóm: ${response.statusCode} - ${response.data}');
    }
  }

  Future<TeamMember> getMemberByTeamAndUserId(int team_id, int user_id) async {
    final response = await authService.get('$path/$team_id/$user_id');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      final data = response.data['data'];
      return TeamMember.fromJson(data);
    } else {
      // Log error for debugging
      print('Failed to get member by team and user: ${response.statusCode}');
      print('Response: ${response.data}');
      throw Exception(
        response.data?['message'] ?? 
        'Lỗi khi tải thành viên nhóm: Status ${response.statusCode}'
      );
    }
  }

  Future<void> createTeamMember(TeamMember reqTeamMemberDTO) async {
    final response = await authService.post('$path', reqTeamMemberDTO.toJson());
    // Laravel returns 201 for successful creation, 200 for other success
    if (response.statusCode == 200 || response.data['status'] == 201) {
      return; // Success
    }
    // If we get here, it's an error
    print('Failed to create team member: ${response.statusCode}');
    print('Response: ${response.data}');
    throw Exception(
      response.data?['message'] ?? 
      'Lỗi khi thêm thành viên vào nhóm: Status ${response.statusCode}'
    );
  }

  Future<void> updateMember(TeamMember reqTeamMemberDTO) async {
    final response = await authService.put('$path', reqTeamMemberDTO.toJson());
    if (response.statusCode != 200 || response.data['status'] != 200) {
      throw Exception('Lỗi khi cập nhật thành viên nhóm');
    }
  }

  Future<void> deleteMember(int id) async {
    final response = await authService.delete('$path/$id');
    if (response.statusCode != 200 || response.data['status'] != 200) {
      throw Exception('Lỗi khi xóa thành viên nhóm');
    }
  }

  Future<void> deleteMemberAndTask(int teamId, int userId) async {
    final response = await authService.delete('$path/tasks/$teamId/$userId');
    if (response.statusCode != 200 || response.data['status'] != 200) {
      throw Exception('Lỗi khi xóa thành vien nhóm');
    }
  }
}
